<!DOCTYPE html>
    <html>
    <head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js">
    </script>
    <script>
    $(document).ready(function(){
      $("#Tbutton").click(function(){
        $("#div2").load("sendInput.php?eventIdValue=44835110021&description=test");
      });
    });

    function setValue(ID,desc){
        document.getElementById("div2").load("sendInput.php?eventIdValue="+ID+"&description="+desc);
    };
    </script>

    <script type="text/javascript">
     $(document).ready(function(){
            $('#Sbutton').click(function(){
                 $.ajax({
                       type: "GET",
                       url: "getCameras.php",
                       /*data: , /*"{'eventIdValue': '44835110021', 'description':'test'}",query="+document.form.textarea.value,*/
                       success: $("#div2").load("sendInput.php?eventIdValue=44835110021&description=test")                       
                     })
            });
        });


    </script>

    </head>
    <body>

    <div id="div1"><h2>jQuery AJAX Load</h2></div>
    <div id="div2"><h2>Let jQuery AJAX Change This Text1</h2></div>
    <button id="Tbutton">Get External Content</button>

    <button onclick="setValue('44835110021','test');">Quick Service</button>
    </body>
    </html>