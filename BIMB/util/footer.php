
        <script>
            // Accordion 
            function myAccFunc() {
              var x = document.getElementById("Demo1");
              if (x.className.indexOf("w3-show") == -1) {
                x.className += " w3-show";
              } else {
                x.className = x.className.replace(" w3-show", "");
              }
            }
            
            // Click on the "Jeans" link on page load to open the accordion for demo purposes
            //document.getElementById("myBtn").click();
            
            // Open and close sidebar
            function w3_open() {
              document.getElementById("mySidebar").style.display = "block";
              document.getElementById("myOverlay").style.display = "block";
            }
             
            function w3_close() {
              document.getElementById("mySidebar").style.display = "none";
              document.getElementById("myOverlay").style.display = "none";
            }
        </script>
        <script src="../util/jquery/jquery.js"></script>
        <script src="../util/jquery-ui-1.12.1/jquery-ui.js"></script>
        <!--script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js"integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="crossorigin="anonymous"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script-->

    