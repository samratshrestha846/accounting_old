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
class User
{

    /**
     * @OA\Property(
     *     title="ID",
     *     description="ID",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(
     *      title="name",
     *      description="Name of the User table",
     *      example="Jonh Doe",
     *      type="string"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="email",
     *      description="Email of the User table",
     *      example="johndoe@gmail.com",
     *      type="string"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="name",
     *      description="Name of the User table",
     *      example="2020-01-27 17:50:45",
     *      format="datetime",
     *      type="string"
     * )
     *
     * @var \DateTime
     */
    public $created_at;

}
