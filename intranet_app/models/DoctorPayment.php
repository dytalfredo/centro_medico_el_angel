<?php
require_once __DIR__ . '/../Core/IntranetDatabase.php';

class DoctorPayment {
    private $conn;
    private $table_name = "doctor_payments";

    public function __construct() {
        $db = IntranetDatabase::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtiene los últimos N pagos a doctores.
     * @param int $limit
     * @return array
     */
    public function getLatest($limit = 10) {
        $payments = [];
        $stmt = $this->conn->prepare("
            SELECT dp.id, dp.payment_date, dp.amount_paid, dp.period_start, dp.period_end,
                   d.name as doctor_name, u.full_name as processed_by_user
            FROM " . $this->table_name . " dp
            JOIN doctors d ON dp.doctor_id = d.id
            LEFT JOIN users u ON dp.processed_by_user_id = u.id
            ORDER BY dp.payment_date DESC LIMIT ?
        ");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $payments[] = $row;
        }
        return $payments;
    }

    /**
     * Calcula los honorarios pendientes de un doctor para un período dado,
     * basado en los ítems de factura relacionados con ese doctor.
     * @param int $doctor_id
     * @param string $start_date 'YYYY-MM-DD'
     * @param string $end_date 'YYYY-MM-DD'
     * @return DECIMAL Honorarios pendientes.
     */
    public function calculatePendingFees($doctor_id, $start_date, $end_date) {
        $total_fees = 0;

        // Sumar los honorarios ganados por el doctor en los ítems de facturas pagadas dentro del período
        $stmt = $this->conn->prepare("
            SELECT SUM(ii.subtotal * (d.fee_percentage / 100)) as earned_fees
            FROM invoice_items ii
            JOIN invoices i ON ii.invoice_id = i.id
            JOIN doctors d ON ii.doctor_id = d.id
            WHERE ii.doctor_id = ?
              AND i.status = 'pagada' -- Cambiado de 'paid' a 'pagada'
              AND i.invoice_date BETWEEN ? AND ?
        ");
        $stmt->bind_param("iss", $doctor_id, $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $total_fees = $row['earned_fees'] ?? 0;
        }

        return $total_fees;
    }

    /**
     * Registra un nuevo pago a un doctor.
     * @param array $data
     * @return int ID del pago o false si falla.
     */
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (doctor_id, payment_date, amount_paid, period_start, period_end, notes, processed_by_user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isddssi", $data['doctor_id'], $data['payment_date'], $data['amount_paid'], $data['period_start'], $data['period_end'], $data['notes'], $data['processed_by_user_id']);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }

    /**
     * Obtiene los honorarios pagados a doctores por mes para un año dado.
     * @param int $year El año para el que se desean los honorarios pagados.
     * @return array Un array asociativo donde la clave es el número del mes y el valor es el total de honorarios pagados.
     * Ej: [1 => 500.00, 2 => 300.00, ...]
     */
    public function getMonthlyPaidFees($year) {
        $monthlyFees = [];
        // Inicializar todos los meses a 0
        for ($i = 1; $i <= 12; $i++) {
            $monthlyFees[$i] = 0.00;
        }

        $stmt = $this->conn->prepare("
            SELECT
                MONTH(payment_date) as month_num,
                SUM(amount_paid) as total_paid_fees
            FROM " . $this->table_name . "
            WHERE YEAR(payment_date) = ?
            GROUP BY MONTH(payment_date)
            ORDER BY MONTH(payment_date) ASC
        ");
        $stmt->bind_param("i", $year);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $monthlyFees[(int)$row['month_num']] = (float)$row['total_paid_fees'];
        }

        return $monthlyFees;
    }

    /**
     * Encuentra un pago existente para un doctor en un período específico.
     * Esto es útil para evitar duplicados si se generan reportes múltiples veces
     * para el mismo período.
     * @param int $doctor_id
     * @param string $period_start 'YYYY-MM-DD'
     * @param string $period_end 'YYYY-MM-DD'
     * @return array|null El pago encontrado (con 'id' y 'amount_paid') o null.
     */
    public function findPaymentByDoctorAndPeriod($doctor_id, $period_start, $period_end) {
        $stmt = $this->conn->prepare("
            SELECT id, amount_paid
            FROM " . $this->table_name . "
            WHERE doctor_id = ? AND period_start = ? AND period_end = ?
            LIMIT 1
        ");
        $stmt->bind_param("iss", $doctor_id, $period_start, $period_end);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Actualiza el monto pagado de un registro de pago existente.
     * @param int $payment_id ID del registro de pago.
     * @param float $new_amount El nuevo monto a registrar.
     * @return bool True si tiene éxito, false si falla.
     */
    public function updatePayment($payment_id, $new_amount) {
        $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET amount_paid = ? WHERE id = ?");
        $stmt->bind_param("di", $new_amount, $payment_id);
        return $stmt->execute();
    }
}
