<?php
include __DIR__.'/BLL/auth_and_cors_middleware.php';
echo Echo_getObj("irl", function ($data, $lingua) {
    return BLL\Response::traduciElemento($data,["infoBase"],$lingua);
});
?>