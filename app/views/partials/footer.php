  <!-- Scripts -->
  <script src="public/vendor/jquery/jquery.min.js"></script>
  <script src="public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="public/js/owl-carousel.js"></script>
  <script src="public/js/animation.js"></script>
  <script src="public/js/imagesloaded.js"></script>
  <script src="public/js/custom.js"></script>
  
  <!-- Script pour gérer le preloader -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
      // Cache le preloader après le chargement
      window.addEventListener('load', function() {
          setTimeout(function() {
              const preloader = document.getElementById('js-preloader');
              if (preloader) {
                  preloader.style.display = 'none';
              }
          }, 1000);
      });
      
      // Fallback au cas où le preloader reste bloqué
      setTimeout(function() {
          const preloader = document.getElementById('js-preloader');
          if (preloader) {
              preloader.style.display = 'none';
          }
      }, 3000);
  });
  </script>
</body>
</html>