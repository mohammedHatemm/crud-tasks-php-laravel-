</main>
<footer class="bg-dark text-white py-3 mt-5 mb-0">
  <div class="container text-center">
    <p>جميع الحقوق محفوظة © <?php echo date('Y'); ?> نظام إدارة الأخبار</p>
  </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  $(document).ready(function() {
    $('.folder').click(function() {
      $(this).toggleClass('open');
      $(this).next('ul').toggleClass('hidden');
    });
  });
</script>

<style>
  html,
  body {
    height: 100%;
    margin: 0;
  }

  body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
  }

  main {
    flex: 1 0 auto;
  }

  footer {
    flex-shrink: 0;
  }
</style>
</body>

</html>
