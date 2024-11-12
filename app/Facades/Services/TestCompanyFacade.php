<?php
namespace App\Facades\Services;

use App\Http\Enums\CompanyEnums;
use App\Facades\Drivers\Test\LuggiDriver;
use App\Facades\Drivers\Test\MixrayDriver;
use App\Facades\Drivers\Test\ToucheDriver;
use App\Facades\Drivers\Test\VoltajDriver;
use App\Facades\Drivers\Test\BigdartDriver;
use App\Facades\Drivers\Test\VaganzaDriver;
use App\Facades\Drivers\Test\ZepkidsDriver;
use App\Facades\Drivers\Test\ZeylandDriver;
use App\Facades\Drivers\Test\TozluDriver;
use App\Facades\Drivers\Test\BarrelsDriver;
use App\Facades\Drivers\Test\PazariumDriver;


class TestCompanyFacade
{
    protected static $companyDrivers = [
        CompanyEnums::ZEPKIDS  => ZepkidsDriver::class,
        CompanyEnums::ZEYLAND  => ZeylandDriver::class,
        CompanyEnums::TOUCHE   => ToucheDriver::class,
        CompanyEnums::BIGDART  => BigdartDriver::class,
        CompanyEnums::MIXRAY   => MixrayDriver::class,
        CompanyEnums::VAGANZA  => VaganzaDriver::class,
        CompanyEnums::LUGGI    => LuggiDriver::class,
        CompanyEnums::VOLTAJ   => VoltajDriver::class,
        CompanyEnums::TOZLU   => TozluDriver::class,
        CompanyEnums::BARRELS   => BarrelsDriver::class,
        CompanyEnums::PAZARIUM   => PazariumDriver::class,
    ];

    public static function driver($driver)
    {
        if (!isset(self::$companyDrivers[$driver])) {
            throw new \InvalidArgumentException("Invalid Payment Driver name: [$driver]");
        }

        $driverClass = self::$companyDrivers[$driver];

        return new $driverClass();
    }
}
