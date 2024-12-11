<?php
namespace App\Facades\Services;

use App\Http\Enums\CompanyEnums;
use App\Facades\Drivers\LuggiDriver;
use App\Facades\Drivers\MixrayDriver;
use App\Facades\Drivers\ToucheDriver;
use App\Facades\Drivers\VoltajDriver;
use App\Facades\Drivers\BigdartDriver;
use App\Facades\Drivers\VaganzaDriver;
use App\Facades\Drivers\ZepkidsDriver;
use App\Facades\Drivers\ZeylandDriver;
use App\Facades\Drivers\TozluDriver;
use App\Facades\Drivers\BarrelsDriver;
use App\Facades\Drivers\PazariumDriver;
use App\Facades\Drivers\CasabonyDriver;
use App\Facades\Drivers\ValibertaDriver;
use App\Facades\Drivers\BreezeDriver;
use App\Facades\Drivers\EminnaDriver;
use App\Facades\Drivers\ModayakamozDriver;
use App\Facades\Drivers\MiteloveDriver;
use App\Facades\Drivers\DenokidsDriver;
use App\Facades\Drivers\PanayirHomeDriver;
use App\Facades\Drivers\JammyBabyDriver;
use App\Facades\Drivers\VavinorDriver;


class CompanyFacade
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
        CompanyEnums::CASABONY   => CasabonyDriver::class,
        CompanyEnums::VALIBERTA   => ValibertaDriver::class,
        CompanyEnums::BREEZE   => BreezeDriver::class,
        CompanyEnums::EMINNA   => EminnaDriver::class,
        CompanyEnums::MODAYAKAMOZ   => ModayakamozDriver::class,
        CompanyEnums::MITELOVE   => MiteloveDriver::class,
        CompanyEnums::DENOKIDS   => DenokidsDriver::class,
        CompanyEnums::PANAYIRHOME   => PanayirHomeDriver::class,
        CompanyEnums::JAMMYBABY   => JammyBabyDriver::class,
        CompanyEnums::VAVINOR   => VavinorDriver::class,
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
