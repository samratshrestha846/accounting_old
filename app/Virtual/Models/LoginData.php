<?php

namespace App\Virtual\Models;

/**
 * @OA\Schema(
 *     title="User",
 *     description="User detail",
 *     @OA\Xml(
 *         name="User"
 *     )
 * )
 */
class LoginData
{

    /**
     * @OA\Property(
     *     title="ID",
     *     description="ID",
     *     example="false"
     * )
     *
     * @var bool
     */
    private $error;

    /**
     * @OA\Property(
     *     title="ID",
     *     description="ID",
     *     format="int64",
     *     example=200
     * )
     *
     * @var integer
     */
    private $status;

    /**
     * @OA\Property(
     *     title="Data",
     *     description="Login user model"
     * )
     *
     * @var \App\Virtual\Models\User
     */
    private $data;

}
