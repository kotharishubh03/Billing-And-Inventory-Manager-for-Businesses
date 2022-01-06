
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

    