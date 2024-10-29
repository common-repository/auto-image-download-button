jQuery(document).ready(function($) {
  $('.custom-download-button').on('click', function(e) {
      e.preventDefault();
      var img = $(this).prev('img');
      var imgSrc = img.attr('src');
      
      if (imgSrc) {
          var link = document.createElement('a');
          link.href = imgSrc;
          link.download = imgSrc.split('/').pop();
          link.click();
      }
  });
});
