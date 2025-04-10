        </div>
    </main>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> UA92 School Management System. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
    // Function to confirm deletions
    function confirmDelete() {
        return confirm('Are you sure you want to delete this record?');
    }
    
    // Function to open modals
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
    }
    
    // Function to close modals
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.className === 'modal') {
            event.target.style.display = 'none';
        }
    }
    </script>
</body>
</html>