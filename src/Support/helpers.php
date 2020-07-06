<?php

### VALIDATION ###

/**
 * Helper de validação do CPF do cliente
 *
 * @param  mixed $cpf
 * @return bool
 */
function is_cpf(int $cpf): bool
{
    $pattern = '/^\d{3}.?\d{3}.?\d{3}-?\d{2}$/';

    if(!preg_match($pattern, $cpf)) {
        return false;
    }
    return true;
}

/**
 * Helper de validação de e-mail
 *
 * @param  string $email
 * @return bool
 */
function is_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}