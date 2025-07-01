<?php
require_once __DIR__ . '/../Core/IntranetDatabase.php';

class InvoiceItem {
    private $conn;
    private $table_name = "invoice_items";

    public function __construct() {
        $db = IntranetDatabase::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Crea un nuevo ítem de factura.
     * @param array $data Datos del ítem (debe incluir invoice_id, service_id, doctor_id, quantity, price_at_invoice, subtotal).
     * @return int ID del nuevo ítem o false si falla.
     */
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (invoice_id, service_id, doctor_id, quantity, price_at_invoice, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiidd", $data['invoice_id'], $data['service_id'], $data['doctor_id'], $data['quantity'], $data['price_at_invoice'], $data['subtotal']);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }

    /**
     * Elimina todos los ítems de una factura.
     * @param int $invoice_id
     * @return bool
     */
    public function deleteByInvoiceId($invoice_id) {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE invoice_id = ?");
        $stmt->bind_param("i", $invoice_id);
        return $stmt->execute();
    }
}
?>
