</main>
        </div>
    </div>

    <!-- Script global de la intranet -->
    <script>
        // Función para mostrar/ocultar modales
        window.toggleModal = function(modalId, show = true) { // Aseguramos que sea global
            const modal = document.getElementById(modalId);
            if (modal) {
                if (show) {
                    modal.classList.remove('hidden'); // <--- ¡Importante! Quitar la clase hidden
                    modal.classList.add('show');
                    document.body.style.overflow = 'hidden'; // Evitar scroll de fondo
                } else {
                    modal.classList.remove('show');
                    modal.classList.add('hidden'); // <--- ¡Importante! Añadir la clase hidden de nuevo al cerrar
                    document.body.style.overflow = ''; // Restaurar scroll de fondo
                }
            }
        };

        // Cerrar modales al hacer clic fuera de ellos
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', function(event) {
                if (event.target === this) {
                    window.toggleModal(this.id, false); // Usar window.toggleModal
                }
            });
        });

        // Event listener para cerrar modales con la tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.show').forEach(modal => {
                    window.toggleModal(modal.id, false); // Usar window.toggleModal
                });
            }
        });
    </script>
</body>
</html>
