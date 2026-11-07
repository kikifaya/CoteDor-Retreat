<?php
function bookingReference($id, $createdAt)
{
    return "CDR-" . date("Y", strtotime($createdAt)) . "-" . str_pad($id, 4, "0", STR_PAD_LEFT);
}
?>
