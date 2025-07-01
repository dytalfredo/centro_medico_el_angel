<?php
require_once __DIR__ . '/../Core/IntranetDatabase.php';
require_once __DIR__ . '/InvoiceItem.php';

class Invoice {
    private $conn;
    private $table_name = "invoices";
    private $invoiceItemModel;

    public function __construct() {
        $db = IntranetDatabase::getInstance();
        $this->conn = $db->getConnection();
        $this->invoiceItemModel = new InvoiceItem();
    }

    /**
     * Obtiene la conexión a la base de datos.
     * Necesario para transacciones en el controlador.
     * @return mysqli
     */
    public function getDbConnection() {
        return $this->conn;
    }

    /**
     * Obtiene una factura por su ID.
     * @param int $id
     * @return array|null
     */
    public function findById($id) {
        $stmt = $this->conn->prepare("
            SELECT i.*, p.name as patient_name, p.id_number as patient_id_number, p.phone, p.address
            FROM " . $this->table_name . " i
            JOIN patients p ON i.patient_id = p.id
            WHERE i.id = ? LIMIT 1
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Obtiene las últimas N facturas o facturas filtradas, con nombres de paciente y el doctor del primer ítem.
     * @param int $limit
     * @param array $filters Opcional. Un array asociativo con filtros (status, patient_id, start_date, end_date).
     * @return array
     */
    public function getLatest($limit = 10, $filters = []) {
        $invoices = [];
        $whereClause = "WHERE 1=1"; // Cláusula base para construir los filtros
        $params = [];
        $paramTypes = "";

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $whereClause .= " AND i.status = ?";
            $params[] = $filters['status'];
            $paramTypes .= "s";
        }
        if (!empty($filters['patient_id'])) {
            $whereClause .= " AND i.patient_id = ?";
            $params[] = $filters['patient_id'];
            $paramTypes .= "i";
        }
        if (!empty($filters['start_date'])) {
            $whereClause .= " AND i.invoice_date >= ?";
            $params[] = $filters['start_date'];
            $paramTypes .= "s";
        }
        if (!empty($filters['end_date'])) {
            $whereClause .= " AND i.invoice_date <= ?";
            $params[] = $filters['end_date'];
            $paramTypes .= "s";
        }

        $sql = "
            SELECT i.id, i.invoice_number, i.invoice_date, i.total_amount, i.status, i.payment_method, i.exchange_rate,
                   p.name as patient_name, p.id_number as patient_id_number,
                   (SELECT d.name FROM invoice_items ii JOIN doctors d ON ii.doctor_id = d.id WHERE ii.invoice_id = i.id ORDER BY ii.id ASC LIMIT 1) as doctor_name
            FROM " . $this->table_name . " i
            JOIN patients p ON i.patient_id = p.id
            {$whereClause}
            ORDER BY i.created_at DESC LIMIT ?
        ";
        $paramTypes .= "i"; // Añadir el tipo para el límite
        $params[] = $limit; // Añadir el límite a los parámetros

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error al preparar la consulta getLatest: " . $this->conn->error);
            return [];
        }

        // Si hay parámetros, vincularlos
        if (!empty($params)) {
            $stmt->bind_param($paramTypes, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $invoices[] = $row;
        }
        $stmt->close(); // Cerrar el statement
        return $invoices;
    }

    /**
     * Cuenta el número de facturas por estado.
     * @param string $status
     * @return int
     */
    public function countInvoicesByStatus($status) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE status = ?");
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    /**
     * Crea una nueva factura y sus ítems.
     * @param array $invoiceData
     * @param array $itemsData
     * @return int ID de la nueva factura o false si falla.
     */
    public function createInvoice($invoiceData, $itemsData) {
        $this->conn->begin_transaction();
        try {
            $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (invoice_number, patient_id, invoice_date, total_amount, status, payment_method, exchange_rate, notes, created_by_user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sisdssdii",
                $invoiceData['invoice_number'],
                $invoiceData['patient_id'],
                $invoiceData['invoice_date'],
                $invoiceData['total_amount'],
                $invoiceData['status'],
                $invoiceData['payment_method'],
                $invoiceData['exchange_rate'],
                $invoiceData['notes'],
                $invoiceData['created_by_user_id']
            );
            $stmt->execute();

            $invoice_id = $this->conn->insert_id;

            foreach ($itemsData as $item) {
                $item['invoice_id'] = $invoice_id;
                $this->invoiceItemModel->create($item);
            }

            $this->conn->commit();
            return $invoice_id;

        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error al crear factura: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza una factura existente y sus ítems.
     * @param int $id ID de la factura.
     * @param array $invoiceData
     * @param array $itemsData
     * @return bool True si tiene éxito, false si falla.
     */
    public function updateInvoice($id, $invoiceData, $itemsData) {
        $this->conn->begin_transaction();
        try {
            $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET patient_id = ?, invoice_date = ?, total_amount = ?, status = ?, payment_method = ?, exchange_rate = ?, notes = ? WHERE id = ?");
            $stmt->bind_param("isdssdssi", // Cambiado de dssdii a dssdssi por el nuevo exchange_rate y notas puede ser más largo
                $invoiceData['patient_id'],
                $invoiceData['invoice_date'],
                $invoiceData['total_amount'],
                $invoiceData['status'],
                $invoiceData['payment_method'],
                $invoiceData['exchange_rate'],
                $invoiceData['notes'],
                $id
            );
            $stmt->execute();

            // Eliminar ítems antiguos e insertar los nuevos
            $this->invoiceItemModel->deleteByInvoiceId($id);
            foreach ($itemsData as $item) {
                $item['invoice_id'] = $id;
                $this->invoiceItemModel->create($item);
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error al actualizar factura: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene los ítems de una factura.
     * @param int $invoice_id
     * @return array
     */
    public function getInvoiceItems($invoice_id) {
        $items = [];
        $stmt = $this->conn->prepare("
            SELECT ii.*, ms.name as service_name, ms.base_price as service_base_price,
                   d.name as doctor_name, d.fee_percentage as doctor_fee_percentage
            FROM invoice_items ii
            JOIN medical_services ms ON ii.service_id = ms.id
            JOIN doctors d ON ii.doctor_id = d.id -- Unir con doctores para obtener el nombre del doctor por ítem
            WHERE ii.invoice_id = ?
        ");
        $stmt->bind_param("i", $invoice_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    }

    /**
     * Elimina una factura.
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Obtiene las ventas mensuales (facturas pagadas) por mes para un año dado.
     * @param int $year El año para el que se desean las ventas.
     * @return array Un array asociativo donde la clave es el número del mes y el valor es el total de ventas.
     * Ej: [1 => 1200.50, 2 => 800.00, ...]
     */
    public function getMonthlySales($year) {
        $monthlySales = [];
        // Inicializar todos los meses a 0
        for ($i = 1; $i <= 12; $i++) {
            $monthlySales[$i] = 0.00;
        }

        $stmt = $this->conn->prepare("
            SELECT
                MONTH(invoice_date) as month_num,
                SUM(total_amount) as total_sales
            FROM " . $this->table_name . "
            WHERE YEAR(invoice_date) = ? AND status = 'pagada' -- Cambiado de 'paid' a 'pagada'
            GROUP BY MONTH(invoice_date)
            ORDER BY MONTH(invoice_date) ASC
        ");
        $stmt->bind_param("i", $year);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $monthlySales[(int)$row['month_num']] = (float)$row['total_sales'];
        }

        return $monthlySales;
    }

    /**
     * Obtiene los honorarios de los doctores para facturas pagadas en un rango de fechas dado,
     * agregados por doctor y por método de pago de la factura original.
     *
     * @param string $startDate Formato 'YYYY-MM-DD'.
     * @param string $endDate Formato 'YYYY-MM-DD'.
     * @return array Un array con la estructura:
     * [
     * doctor_id => [
     * 'doctor_name' => 'Nombre del Doctor',
     * 'specialty' => 'Especialidad del Doctor',
     * 'payments' => [
     * payment_method => total_amount_for_this_method_usd
     * ]
     * ]
     * ]
     */
    public function getDailyDoctorFeesByInvoicePayment($startDate, $endDate) {
        $doctorFees = [];

        $stmt = $this->conn->prepare("
            SELECT
                ii.doctor_id,
                d.name as doctor_name,
                d.specialty,
                d.fee_percentage,
                i.payment_method,
                SUM(ii.subtotal) as total_subtotal_service_usd
            FROM invoice_items ii
            JOIN invoices i ON ii.invoice_id = i.id
            JOIN doctors d ON ii.doctor_id = d.id
            WHERE i.status = 'pagada'
              AND i.invoice_date BETWEEN ? AND ?
            GROUP BY ii.doctor_id, i.payment_method
            ORDER BY d.name, i.payment_method
        ");
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $doctorId = $row['doctor_id'];
            $paymentMethod = $row['payment_method'];
            $totalSubtotalServiceUsd = (float)$row['total_subtotal_service_usd'];
            $feePercentage = (float)$row['fee_percentage'];

            $calculatedFee = $totalSubtotalServiceUsd * ($feePercentage / 100);

            if (!isset($doctorFees[$doctorId])) {
                $doctorFees[$doctorId] = [
                    'doctor_name' => $row['doctor_name'],
                    'specialty' => $row['specialty'],
                    'payments' => []
                ];
            }
            // Agrega el monto calculado al método de pago específico
            // Usa el método de pago de la factura original como clave
            $doctorFees[$doctorId]['payments'][$paymentMethod] = ($doctorFees[$doctorId]['payments'][$paymentMethod] ?? 0) + $calculatedFee;
        }

        return $doctorFees;
    }
}
