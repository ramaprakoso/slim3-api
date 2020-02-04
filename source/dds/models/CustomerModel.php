<?php
namespace DDSModels;

class CustomerModel extends \Illuminate\Database\Eloquent\Model
{
    /*
     * just dont remove
     * set disable updated_at & created_at fields
     */
    public $timestamps = false;

    /*
     * table name
     */
    protected $table = 'customers';

    /*
     * primary key is not id
     */
    protected $primaryKey = 'customerNumber';

    /*
     * connection name core / test
     */
    protected $connection = 'test';
}
