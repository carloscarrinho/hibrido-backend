<?php

### VALIDATION ###
function is_cpf(int $cpf): bool
{
    $pattern = '/^\d{3}.?\d{3}.?\d{3}-?\d{2}$/';

    if(!preg_match($pattern, $cpf)) {
        return false;
    }
    return true;
}

function is_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}