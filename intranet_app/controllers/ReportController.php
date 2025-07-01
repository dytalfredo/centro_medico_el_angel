<?php
require_once __DIR__ . '/../Core/BaseIntranetController.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../models/Invoice.php';
require_once __DIR__ . '/../models/DoctorPayment.php';
require_once __DIR__ . '/../models/AuditLog.php'; // Para registrar acciones

// Incluir la librería FPDF. Ajusta la ruta si es diferente en tu proyecto.
// Asegúrate de que la carpeta 'fpdf' esté dentro de 'intranet_app/vendor/'
require_once __DIR__ . '/../vendor/fpdf/fpdf.php';


class ReportController extends BaseIntranetController {
    private $invoiceModel;
    private $doctorPaymentModel;
    private $auditLogModel;
    private $baseUrl = '/elangel_medical_center/admin';

    public function __construct() {
        $this->invoiceModel = new Invoice();
        $this->doctorPaymentModel = new DoctorPayment();
        $this->auditLogModel = new AuditLog();
    }

    /**
     * Muestra la página principal de reportes.
     * Solo accesible para administradores.
     */
    public function index() {
        Auth::requireLogin($this->baseUrl . '/login', 'admin'); // Solo admin puede acceder

        $currentYear = date('Y');

        // Obtener datos para el gráfico de ventas
        $monthlySalesData = $this->invoiceModel->getMonthlySales($currentYear);
        // Obtener datos para el gráfico de honorarios pagados
        $monthlyPaidFeesData = $this->doctorPaymentModel->getMonthlyPaidFees($currentYear);

        $this->render('reports/index.php', [
            'monthlySalesData' => json_encode(array_values($monthlySalesData)), // Convertir a array indexado para JS
            'monthlyPaidFeesData' => json_encode(array_values($monthlyPaidFeesData)), // Convertir a array indexado para JS
            'currentYear' => $currentYear,
            'flash_message' => $this->getFlashMessage(),
            'user' => Auth::user()
        ]);
    }

    /**
     * Genera un reporte en PDF utilizando FPDF.
     * Este endpoint recibe parámetros para el tipo de reporte y rango de fechas.
     */
    public function generatePdfReport() {
        Auth::requireLogin($this->baseUrl . '/login', 'admin'); // Solo admin puede generar reportes

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reportType = $_POST['report_type'] ?? 'general';
            $startDate = $_POST['start_date'] ?? date('Y-m-d'); // Default to today
            $endDate = $_POST['end_date'] ?? date('Y-m-d');   // Default to today
            $currentUserId = Auth::user()['id'] ?? null;
            $currentUsername = Auth::user()['username'] ?? 'Desconocido';

            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);

            $title = '';
            $reportContent = [];
            $transactionSuccessful = true; // Para controlar la transacción de la BD

            // Helper para mapear métodos de pago a nombres amigables
            $paymentMethodNames = [
                'bolivares_pago_movil' => 'Bolívares (Pago Móvil)',
                'bs_efectivo' => 'Bolívares (Efectivo)',
                'dolares_efectivo' => 'Dólares (Efectivo)',
                'dolares_zelle' => 'Dólares (Zelle)',
            ];

            switch ($reportType) {
                case 'ventas_mensuales':
                    $title = 'Reporte de Ventas Mensuales';
                    $salesData = $this->invoiceModel->getMonthlySales(date('Y')); // Obtener ventas del año actual
                    $reportContent[] = "Ventas por Mes (Año " . date('Y') . "):";
                    foreach ($salesData as $monthNum => $totalSales) {
                        $monthName = date('F', mktime(0, 0, 0, $monthNum, 10)); // Nombre del mes
                        $reportContent[] = ucfirst($monthName) . ": $" . number_format($totalSales, 2);
                    }
                    break;

                case 'honorarios_medicos':
                    $title = 'Reporte de Honorarios Médicos Pagados';
                    $feesData = $this->doctorPaymentModel->getMonthlyPaidFees(date('Y')); // Obtener honorarios del año actual
                    $reportContent[] = "Honorarios Pagados por Mes (Año " . date('Y') . "):";
                    foreach ($feesData as $monthNum => $totalFees) {
                        $monthName = date('F', mktime(0, 0, 0, $monthNum, 10)); // Nombre del mes
                        $reportContent[] = ucfirst($monthName) . ": $" . number_format($totalFees, 2);
                    }
                    break;

                case 'honorarios_diarios_medicos':
                    $title = 'Reporte de Honorarios Médicos a Pagar';
                    $feesByDoctorAndMethod = $this->invoiceModel->getDailyDoctorFeesByInvoicePayment($startDate, $endDate);

                    $reportContent[] = "Período: Del " . date('d/m/Y', strtotime($startDate)) . " al " . date('d/m/Y', strtotime($endDate));
                    $reportContent[] = ""; // Espacio en blanco

                    if (empty($feesByDoctorAndMethod)) {
                        $reportContent[] = "No hay honorarios pendientes de pago para el período seleccionado.";
                        $this->setFlashMessage('info', 'No hay honorarios pendientes de pago para el período seleccionado.');
                    } else {
                        // Iniciar transacción para asegurar que todos los pagos se registren o ninguno
                        $this->invoiceModel->getDbConnection()->begin_transaction();
                        try {
                            foreach ($feesByDoctorAndMethod as $doctorId => $data) {
                                $doctorName = $data['doctor_name'];
                                $specialty = $data['specialty'];
                                $reportContent[] = utf8_decode("Médico: {$doctorName} ({$specialty})");
                                $totalDoctorAmountForPeriod = 0; // Para sumar todos los pagos del doctor en el período

                                foreach ($data['payments'] as $paymentMethod => $amount) {
                                    $paymentAmount = round($amount, 2); // Redondear a 2 decimales
                                    $methodName = $paymentMethodNames[$paymentMethod] ?? $paymentMethod;
                                    $reportContent[] = utf8_decode("  - {$methodName}: $" . number_format($paymentAmount, 2));
                                    $totalDoctorAmountForPeriod += $paymentAmount;
                                }

                                // Registrar o actualizar el pago total para este doctor en este período
                                $existingPayment = $this->doctorPaymentModel->findPaymentByDoctorAndPeriod($doctorId, $startDate, $endDate);

                                $paymentData = [
                                    'doctor_id' => $doctorId,
                                    'payment_date' => date('Y-m-d'), // Fecha actual de registro del pago
                                    'amount_paid' => $totalDoctorAmountForPeriod, // Monto total de honorarios para este doctor en este período
                                    'period_start' => $startDate,
                                    'period_end' => $endDate,
                                    'notes' => utf8_decode("Pago de honorarios por facturas liquidadas del período " . date('d/m/Y', strtotime($startDate)) . " al " . date('d/m/Y', strtotime($endDate))),
                                    'processed_by_user_id' => $currentUserId
                                ];

                                if ($existingPayment) {
                                    // Si ya existe, actualizamos el monto.
                                    // Si corres el reporte varias veces para el mismo día y hay nuevas facturas,
                                    // el monto se actualizará. Si no hay nuevas facturas, el monto seguirá siendo el mismo.
                                    $this->doctorPaymentModel->updatePayment($existingPayment['id'], $totalDoctorAmountForPeriod);
                                } else {
                                    // Si no existe, creamos un nuevo registro
                                    $this->doctorPaymentModel->create($paymentData);
                                }
                                $reportContent[] = ""; // Espacio entre doctores
                            }
                            $this->invoiceModel->getDbConnection()->commit(); // Confirmar transacción
                            $this->setFlashMessage('success', 'Reporte de honorarios generado y pagos registrados exitosamente.');
                            $this->auditLogModel->logAction($currentUserId, 'daily_doctor_fees_report_generated', "Reporte de honorarios diario generado y pagos registrados para {$startDate} - {$endDate}.");
                        } catch (Exception $e) {
                            $this->invoiceModel->getDbConnection()->rollback(); // Revertir si hay un error
                            error_log("Error al generar reporte de honorarios y registrar pagos: " . $e->getMessage());
                            $this->setFlashMessage('error', 'Error al generar el reporte de honorarios y registrar los pagos.');
                            $transactionSuccessful = false; // Marcar transacción como fallida
                        }
                    }
                    break;

                case 'facturas_por_paciente':
                    $title = 'Reporte de Facturas por Paciente';
                    $reportContent[] = "Este reporte requiere lógica adicional para filtrar y listar facturas por paciente.";
                    $reportContent[] = "Rango de Fechas: Del {$startDate} al {$endDate}";
                    // Aquí deberías llamar a un método en InvoiceModel para obtener las facturas del paciente
                    // $invoicesByPatient = $this->invoiceModel->getInvoicesByPatient($patientId, $startDate, $endDate);
                    break;
                default:
                    $title = 'Reporte Personalizado';
                    $reportContent[] = "Tipo de reporte no reconocido o general.";
                    $reportContent[] = "Rango de Fechas: Del {$startDate} al {$endDate}";
                    break;
            }

            // Solo genera el PDF si la transacción de la base de datos fue exitosa (o si no hubo transacción)
            if ($transactionSuccessful) {
                $pdf->Cell(0, 10, utf8_decode($title), 0, 1, 'C');
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(0, 7, utf8_decode("Generado el: " . date('d/m/Y H:i:s') . " por: " . $currentUsername), 0, 1, 'C');
                $pdf->Ln(10); // Salto de línea

                $pdf->SetFont('Arial', '', 12);
                foreach ($reportContent as $line) {
                    $pdf->Cell(0, 8, utf8_decode($line), 0, 1);
                }

                $pdf->Output('D', utf8_decode('reporte_' . str_replace(' ', '_', strtolower($title)) . '_' . date('Ymd_His') . '.pdf')); // 'D' para descargar
                exit; // Termina la ejecución después de generar el PDF
            } else {
                 $this->redirect($this->baseUrl . '/reportes'); // Redirige si la transacción falló
            }

        } else {
            $this->redirect($this->baseUrl . '/reportes'); // Si no es POST, redirigir
        }
    }
}
