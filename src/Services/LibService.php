<?php

/**
 * Created by PhpStorm.
 * User: Sascha.Pflueger
 * Date: 03.07.2017
 * Time: 09:21
 */

use Plenty\Modules\Plugin\Libs\Contracts\LibraryCallContract;

class LibService
{
    /**
     * @var LibraryCallContract
     */
    private $libraryCall;

    /**
     * LibService constructor.
     * @param LibraryCallContract $libraryCallContract
     */
    public function __construct(LibraryCallContract $libraryCallContract)
    {
        $this->libraryCall = $libraryCallContract;
    }
}


