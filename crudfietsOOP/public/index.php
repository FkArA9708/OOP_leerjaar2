<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
     require_once __DIR__ . '/../vendor/autoload.php';
    
    use crud_fiets\crudfietsOOP\Fiets;
    
    
    $fiets = new Fiets();
    $fiets->crudMain();
    ?>
</body>
</html>