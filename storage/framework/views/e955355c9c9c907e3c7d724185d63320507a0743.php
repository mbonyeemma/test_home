<html>
<head>
</head>
   <body>

<script>

    window.onload = function () {
        window.print();
    }
  </script>


    <div style="width:100%; text-align:center; padding-top:50px; padding-bottom:50px;">
    <h1 style="text-transform:uppercase"><?php echo e($facility->name); ?></h1>
    	<?php echo QrCode::size(750)->generate('900519'); ?>

    </div>

    </body>
</html>
