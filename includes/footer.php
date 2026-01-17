    </main>

    <footer class="footer">
        <div class="footer-content">
            <p class="footer-text">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
            <div class="footer-links">
                <a href="/about.php">About</a>
                <a href="/contact.php">Contact</a>
                <a href="/support.php">Support Us</a>
            </div>
        </div>
    </footer>

    <script>
        // Mobile navigation toggle
        function toggleNav() {
            document.getElementById('navMenu').classList.toggle('active');
        }

        // Close nav when clicking outside
        document.addEventListener('click', function(e) {
            const nav = document.getElementById('navMenu');
            const toggle = document.querySelector('.nav-toggle');
            if (!nav.contains(e.target) && !toggle.contains(e.target)) {
                nav.classList.remove('active');
            }
        });
    </script>
</body>
</html>
