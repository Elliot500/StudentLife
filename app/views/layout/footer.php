
</div><!-- /.shell -->

<script>
(function () {
  var cards = document.querySelectorAll('.glass');
  cards.forEach(function (card) {
    card.addEventListener('pointermove', function (e) {
      var r = card.getBoundingClientRect();
      card.style.setProperty('--mx', (e.clientX - r.left) + 'px');
      card.style.setProperty('--my', (e.clientY - r.top)  + 'px');
    });
    card.addEventListener('pointerleave', function () {
      card.style.setProperty('--mx', '-500px');
      card.style.setProperty('--my', '-500px');
    });
  });
}());
</script>

</body>
</html>
