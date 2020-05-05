 <!-- footer -->
 <footer id="footer">
     Copyright <a href="">Shoe You</a>. All Rights Reserved.
 </footer>

 <script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
 <script>
     $(function() {
         var $ftr = $('#footer');
         if (window.innerHeight > $ftr.offset().top + $ftr.outerHeight()) {
             $ftr.attr({
                 'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) + 'px;'
             });
         }
     });
 </script>
 </body>

 </html>  