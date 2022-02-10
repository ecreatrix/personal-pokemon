<?php

namespace App\Services\TCG\Interfaces;

use Pokemon\Models\Model;

/**
 * Interface QueriableResourceInterface
 *
 * @package App\Services\TCG\Interfaces
 */
interface QueriableResourceInterface extends ResourceInterface {
    /**
     * @param string $identifier
     *
     * @return Model|null
     */
    public function find( $identifier );

    /**
     * @param array $query
     *
     * @return QueriableResourceInterface
     */
    public function where( array $query );
}
