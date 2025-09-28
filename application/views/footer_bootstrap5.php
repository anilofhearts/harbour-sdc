        </div> <!-- End container-fluid -->
    </div> <!-- End page-wrapper -->

    <!-- Bootstrap 5 JS Bundle (includes Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (already loaded in header, but ensuring it's available) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Select2 JS -->
    <script src="<?=base_url()?>public/assets/libs/select2/dist/js/select2.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Perfect Scrollbar -->
    <script src="<?php echo base_url(); ?>public/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    
    <!-- Sparkline -->
    <script src="<?php echo base_url(); ?>public/assets/extra-libs/sparkline/sparkline.js"></script>
    
    <!-- Waves Effects -->
    <script src="<?php echo base_url(); ?>public/dist/js/waves.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="<?php echo base_url(); ?>public/dist/js/custom.min.js"></script>
    
    <!-- Bootbox for confirmations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
    
    <!-- CSRF Token Setup -->
    <script>
        // Setup CSRF token for AJAX requests
        $.ajaxSetup({
            data: {
                '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
            }
        });
        
        // Initialize DataTables
        $(document).ready(function() {
            if ($.fn.DataTable) {
                $('.data-table').DataTable({
                    "responsive": true,
                    "pageLength": 25,
                    "order": [[0, "desc"]]
                });
            }
            
            // Initialize Select2
            if ($.fn.select2) {
                $('.select2').select2({
                    theme: 'bootstrap-5'
                });
            }
        });
        
        // Confirmation function
        function doConfirm(message = "Are you sure you want to delete this record?") {
            return confirm(message);
        }
        
        // Disable right-click context menu
        $(document).bind("contextmenu", function(e) {
            e.preventDefault();
        });
        
        // Disable text selection
        document.onselectstart = function() { return false; };
    </script>
    
    <!-- Footer -->
    <footer class="bg-light text-center text-lg-start mt-5">
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.1);">
            © 2024 Harbour Management System. All Rights Reserved.
        </div>
    </footer>
</body>
</html>
