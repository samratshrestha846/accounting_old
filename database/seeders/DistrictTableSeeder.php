<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DistrictTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tbl_district = array(
            array('id' => '1','dist_id' => '09','dist_name' => 'Sunsari','province_id' => 1),
            array('id' => '2','dist_id' => '10','dist_name' => 'Morang','province_id' => 1),
            array('id' => '3','dist_id' => '01','dist_name' => 'Taplejung','province_id' => 1),
            array('id' => '4','dist_id' => '02','dist_name' => 'Panchthar','province_id' => 1),
            array('id' => '5','dist_id' => '03','dist_name' => 'Ilam','province_id' => 1),
            array('id' => '6','dist_id' => '04','dist_name' => 'Jhapa','province_id' => 1),
            array('id' => '7','dist_id' => '05','dist_name' => 'Sankhuwasabha','province_id' => 1),
            array('id' => '8','dist_id' => '06','dist_name' => 'Tehrathum','province_id' => 1),
            array('id' => '9','dist_id' => '07','dist_name' => 'Bhojpur','province_id' => 1),
            array('id' => '10','dist_id' => '08','dist_name' => 'Dhankuta','province_id' => 1),
            array('id' => '11','dist_id' => '11','dist_name' => 'Solukhumbu','province_id' => 1),
            array('id' => '12','dist_id' => '12','dist_name' => 'Khothang','province_id' => 1),
            array('id' => '13','dist_id' => '13','dist_name' => 'Udayapur','province_id' => 1),
            array('id' => '14','dist_id' => '14','dist_name' => 'Okhaldhunga','province_id' => 1),
            array('id' => '15','dist_id' => '17','dist_name' => 'Dhanusha','province_id' => 2),
            array('id' => '16','dist_id' => '33','dist_name' => 'Bara','province_id' => 2),
            array('id' => '17','dist_id' => '34','dist_name' => 'Parsa','province_id' => 2),
            array('id' => '18','dist_id' => '15','dist_name' => 'Saptari','province_id' => 2),
            array('id' => '19','dist_id' => '16','dist_name' => 'Siraha','province_id' => 2),
            array('id' => '20','dist_id' => '18','dist_name' => 'Mahottari','province_id' => 2),
            array('id' => '21','dist_id' => '19','dist_name' => 'Sarlahi','province_id' => 2),
            array('id' => '22','dist_id' => '32','dist_name' => 'Rautahat','province_id' => 2),
            array('id' => '23','dist_id' => '27','dist_name' => 'Kathmandu','province_id' => 3),
            array('id' => '24','dist_id' => '28','dist_name' => 'Lalitpur','province_id' => 3),
            array('id' => '25','dist_id' => '35','dist_name' => 'Chitwan','province_id' => 3),
            array('id' => '26','dist_id' => '31','dist_name' => 'Makwanpur','province_id' => 3),
            array('id' => '27','dist_id' => '20','dist_name' => 'Sindhuli','province_id' => 3),
            array('id' => '28','dist_id' => '21','dist_name' => 'Ramechhap','province_id' => 3),
            array('id' => '29','dist_id' => '22','dist_name' => 'Dolakha','province_id' => 3),
            array('id' => '30','dist_id' => '23','dist_name' => 'Sindhupalanchowk','province_id' => 3),
            array('id' => '31','dist_id' => '25','dist_name' => 'Dhading','province_id' => 3),
            array('id' => '32','dist_id' => '26','dist_name' => 'Nuwakot','province_id' => 3),
            array('id' => '33','dist_id' => '29','dist_name' => 'Bhaktapur','province_id' => 3),
            array('id' => '34','dist_id' => '30','dist_name' => 'Kavrepalanchowk','province_id' => 3),
            array('id' => '35','dist_id' => '24','dist_name' => 'Rasuwa','province_id' => 3),
            array('id' => '36','dist_id' => '47','dist_name' => 'Kaski','province_id' =>4),
            array('id' => '37','dist_id' => '76','dist_name' => 'Nawalparasi (बर्दघाट सुस्ता पूर्व)','province_id' =>4),
            array('id' => '38','dist_id' => '42','dist_name' => 'Syangja','province_id' =>4),
            array('id' => '39','dist_id' => '43','dist_name' => 'Tanahu','province_id' =>4),
            array('id' => '40','dist_id' => '44','dist_name' => 'Gorkha','province_id' =>4),
            array('id' => '41','dist_id' => '46','dist_name' => 'Lamjung','province_id' =>4),
            array('id' => '42','dist_id' => '48','dist_name' => 'Parbat','province_id' =>4),
            array('id' => '43','dist_id' => '49','dist_name' => 'Baglung','province_id' =>4),
            array('id' => '44','dist_id' => '50','dist_name' => 'Myagdi','province_id' =>4),
            array('id' => '45','dist_id' => '45','dist_name' => 'Manang','province_id' =>4),
            array('id' => '46','dist_id' => '51','dist_name' => 'Mustang','province_id' =>4),
            array('id' => '47','dist_id' => '37','dist_name' => 'Rupandehi','province_id' => 5),
            array('id' => '48','dist_id' => '60','dist_name' => 'Dang','province_id' => 5),
            array('id' => '49','dist_id' => '62','dist_name' => 'Banke','province_id' => 5),
            array('id' => '50','dist_id' => '36','dist_name' => 'Nawalparasi (बर्दघाट सुस्ता पश्चिम)','province_id' => 5),
            array('id' => '51','dist_id' => '38','dist_name' => 'Kapilvastu','province_id' => 5),
            array('id' => '52','dist_id' => '39','dist_name' => 'Arghakhanchi','province_id' => 5),
            array('id' => '53','dist_id' => '40','dist_name' => 'Palpa','province_id' => 5),
            array('id' => '54','dist_id' => '41','dist_name' => 'Gulmi','province_id' => 5),
            array('id' => '55','dist_id' => '58','dist_name' => 'Rolpa','province_id' => 5),
            array('id' => '56','dist_id' => '59','dist_name' => 'Pyuthan','province_id' => 5),
            array('id' => '57','dist_id' => '63','dist_name' => 'Bardia','province_id' => 5),
            array('id' => '58','dist_id' => '77','dist_name' => 'Eastern Rukum','province_id' => 5),
            array('id' => '59','dist_id' => '52','dist_name' => 'Mugu','province_id' =>6),
            array('id' => '60','dist_id' => '53','dist_name' => 'Dolpa','province_id' =>6),
            array('id' => '61','dist_id' => '55','dist_name' => 'Jumla','province_id' =>6),
            array('id' => '62','dist_id' => '56','dist_name' => 'Kalikot','province_id' =>6),
            array('id' => '63','dist_id' => '57','dist_name' => 'Western Rukum','province_id' =>6),
            array('id' => '64','dist_id' => '61','dist_name' => 'Salyan','province_id' =>6),
            array('id' => '65','dist_id' => '64','dist_name' => 'Surkhet','province_id' =>6),
            array('id' => '66','dist_id' => '65','dist_name' => 'Jajarkot','province_id' =>6),
            array('id' => '67','dist_id' => '66','dist_name' => 'Dailekh','province_id' =>6),
            array('id' => '68','dist_id' => '54','dist_name' => 'Humla','province_id' =>6),
            array('id' => '69','dist_id' => '67','dist_name' => 'Kailali','province_id' => 7),
            array('id' => '70','dist_id' => '68','dist_name' => 'Doti','province_id' => 7),
            array('id' => '71','dist_id' => '69','dist_name' => 'Accham','province_id' => 7),
            array('id' => '72','dist_id' => '70','dist_name' => 'Bajura','province_id' => 7),
            array('id' => '73','dist_id' => '71','dist_name' => 'Bajhang','province_id' => 7),
            array('id' => '74','dist_id' => '72','dist_name' => 'Darchula','province_id' => 7),
            array('id' => '75','dist_id' => '73','dist_name' => 'Baitadi','province_id' => 7),
            array('id' => '76','dist_id' => '74','dist_name' => 'Dadeldhura','province_id' => 7),
            array('id' => '77','dist_id' => '75','dist_name' => 'Kanchanpur','province_id' => 7)
        );
        \DB::table('districts')->insert($tbl_district);

    }
}
