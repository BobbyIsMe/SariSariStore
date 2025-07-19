<script>

            function openModal() {
                document.body.classList.add('modal-open');
                document.documentElement.classList.add('modal-open');
            }
            
            function closeModal() {
                document.body.classList.remove('modal-open');
                document.documentElement.classList.remove('modal-open');
            }
            

            document.addEventListener('DOMContentLoaded', function() {
                const modals = document.querySelectorAll('.modal');
                modals.forEach(modal => {
                    modal.addEventListener('show.bs.modal', function() {
                        openModal();
                    });
                    
                    modal.addEventListener('hidden.bs.modal', function() {
                        closeModal();
                    });
                });
            });
        </script>