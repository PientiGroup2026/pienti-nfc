(function () {
  var go = document.getElementById('go');
  document.querySelectorAll('a[data-go]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      if (e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return;
      e.preventDefault();
      if (go) go.classList.add('on');
      setTimeout(function () { window.location.href = a.href; }, 280);
    });
  });
  window.addEventListener('pageshow', function () { if (go) go.classList.remove('on'); });
  var y = document.getElementById('y');
  if (y) y.textContent = new Date().getFullYear();
})();
