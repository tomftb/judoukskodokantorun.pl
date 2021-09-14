<?php
if ($_SESSION["id_user"]==1){ echo "check_len v2</br>";};
function check_len(&$check, $field, $max, &$err_field, $err="", $min, $min_err="")

{

  if (strlen($field) > $max) {
								if ($err == ""){
												$err = $msg->err_maxlen($max);
								};
								$err_field = $err."</br><span CLASS=\"S_ERR_DANE2\">Wpisano - <span class=\"S_ERR_DANE\">".strlen($field)."</span></span>";
								if ($check==true) $check = false;
  }
  if (strlen($field) < $min) {
								if ($min_err == ""){
													$min_err = $msg->err_minlen($min);
								};
								$err_field = $min_err."</br><span CLASS=\"S_ERR_DANE2\">Wpisano - <span class=\"S_ERR_DANE\">".strlen($field)."</span></span>";
								if ($check==true) $check = false;
  };
};
?>