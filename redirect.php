<?php
 
if($_GET['u'] && !empty($_GET['info'])){
    func_action_redirect_info($_GET['u']);
}else if($_GET['u']){
    func_action_redirect($_GET['u']);
}
 
?>
