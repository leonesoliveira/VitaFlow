<?php

namespace Mad\Util;

use Adianti\Core\AdiantiApplicationConfig;
use Exception;

/**
 * @package    util
 * @author     Matheus Agnes Dias
 * @copyright  Copyright (c) 2025 Mad Solutions Ltd. (http://www.madbuilder.com.br)
 */

class Crypt
{
    private static $key;
    
    /**
     * Inicializa a chave de criptografia
     * @throws Exception se a chave não estiver configurada
     */
    private static function initKey()
    {
        if (empty(self::$key)) {
            $key = AdiantiApplicationConfig::get()['general']['token'] ?? AdiantiApplicationConfig::get()['general']['seed'];
            
            if (empty($key)) {
                throw new Exception('Encryption key not set in application config');
            }
            
            // Usa a chave configurada ao invés de gerar uma nova
            self::$key = $key;
        }
    }
    
    /**
     * Criptografa uma string
     * @param string $data String para criptografar
     * @return string String criptografada em base64
     * @throws Exception
     */
    public static function encryptString(string $data): string
    {
        try {
            self::initKey();
            
            if (empty($data)) {
                return '';
            }
            
            // Verifica se a extensão Sodium está disponível
            if (function_exists('sodium_crypto_generichash')) {
                // Método de criptografia com Sodium
                $key = sodium_crypto_generichash(self::$key, '', SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
                $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
                $encrypted = sodium_crypto_secretbox($data, $nonce, $key);
                
                // Combina nonce e dados criptografados e converte para base64
                return 'sodium:' . base64_encode($nonce . $encrypted);
            } else {
                // Método alternativo com OpenSSL
                $ivlen = openssl_cipher_iv_length($cipher = 'AES-256-CBC');
                $iv = openssl_random_pseudo_bytes($ivlen);
                $key = hash('sha256', self::$key, true);
                $encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                
                if ($encrypted === false) {
                    throw new Exception('OpenSSL encryption failed');
                }
                
                // Combina IV e dados criptografados e converte para base64
                return 'openssl:' . base64_encode($iv . $encrypted);
            }
        } catch (Exception $e) {
            throw new Exception('Encryption error: ' . $e->getMessage());
        }
    }
    
    /**
     * Descriptografa uma string
     * @param string $encryptedData String criptografada em base64
     * @return string String descriptografada
     * @throws Exception
     */
    public static function decryptString(string $encryptedData): string
    {
        try {
            self::initKey();
            
            if (empty($encryptedData)) {
                return '';
            }
            
            // Verifica o método usado na criptografia
            if (strpos($encryptedData, 'sodium:') === 0) {
                // Descriptografia usando Sodium
                if (!function_exists('sodium_crypto_secretbox_open')) {
                    throw new Exception('Sodium extension required for decryption but not available');
                }
                
                $base64Data = substr($encryptedData, 7); // Remove 'sodium:'
                $decoded = base64_decode($base64Data);
                
                if ($decoded === false) {
                    throw new Exception('Invalid base64 data');
                }
                
                if (strlen($decoded) < SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
                    throw new Exception('Invalid encrypted data');
                }
                
                // Deriva a mesma chave usando a chave configurada
                $key = sodium_crypto_generichash(self::$key, '', SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
                
                // Separa o nonce dos dados criptografados
                $nonce = substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
                $ciphertext = substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
                
                $decrypted = sodium_crypto_secretbox_open($ciphertext, $nonce, $key);
                
                if ($decrypted === false) {
                    throw new Exception('Sodium decryption failed');
                }
                
                return $decrypted;
            } elseif (strpos($encryptedData, 'openssl:') === 0) {
                // Descriptografia usando OpenSSL
                $base64Data = substr($encryptedData, 8); // Remove 'openssl:'
                $decoded = base64_decode($base64Data);
                
                if ($decoded === false) {
                    throw new Exception('Invalid base64 data');
                }
                
                $cipher = 'AES-256-CBC';
                $ivlen = openssl_cipher_iv_length($cipher);
                
                if (strlen($decoded) < $ivlen) {
                    throw new Exception('Invalid encrypted data');
                }
                
                // Separa o IV dos dados criptografados
                $iv = substr($decoded, 0, $ivlen);
                $ciphertext = substr($decoded, $ivlen);
                $key = hash('sha256', self::$key, true);
                
                $decrypted = openssl_decrypt($ciphertext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                
                if ($decrypted === false) {
                    throw new Exception('OpenSSL decryption failed');
                }
                
                return $decrypted;
            } else {
                // Dados antigos sem prefixo (assume Sodium para compatibilidade)
                if (!function_exists('sodium_crypto_secretbox_open')) {
                    throw new Exception('Sodium extension required for decryption but not available');
                }
                
                $decoded = base64_decode($encryptedData);
                
                if ($decoded === false) {
                    throw new Exception('Invalid base64 data');
                }
                
                if (strlen($decoded) < SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
                    throw new Exception('Invalid encrypted data');
                }
                
                // Deriva a mesma chave usando a chave configurada
                $key = sodium_crypto_generichash(self::$key, '', SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
                
                // Separa o nonce dos dados criptografados
                $nonce = substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
                $ciphertext = substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
                
                $decrypted = sodium_crypto_secretbox_open($ciphertext, $nonce, $key);
                
                if ($decrypted === false) {
                    throw new Exception('Decryption failed');
                }
                
                return $decrypted;
            }
        } catch (Exception $e) {
            throw new Exception('Decryption error: ' . $e->getMessage());
        }
    }
}