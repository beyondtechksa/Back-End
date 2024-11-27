<?php
namespace App\Http\Enums;

use BenSampo\Enum\Enum;

final class CompanyEnums extends Enum
{
    const TRENDYOL = 'trendyol';
    const ZEPKIDS = 'zepkids';
    const ZEYLAND = 'zeyland';
    const TOUCHE = 'touché';
    const BIGDART = 'bigdart';
    const VAGANZA = 'vaganza';
    const LUGGI = 'luggi';
    const VOLTAJ = 'voltaj';
    const TOZLU = 'tozlu';
    const BARRELS = 'barrels and oil';
    const PAZARIUM = 'pazarium new';
    const CASABONY = 'casabony';
    const VALIBERTA = 'valiberta';
    const MIXRAY = 'mixray';
    const BREEZE = 'breeze';
    const EMINNA = 'eminna';
    const MODAYAKAMOZ = 'modayakamoz';
    const MITELOVE = 'mite love';
    const DENOKIDS = 'denokids';

    const COMPANY_CATEGORIES = [
        self::TRENDYOL => [],
        self::ZEPKIDS => ["Kids" => 1, "Baby" => 2, "Women" => 3],
        self::ZEYLAND => ["Kids" => 1, "Baby" => 2],
        self::TOUCHE => ["Women" => 3],
        self::BIGDART => ["Women" => 547],
        self::MIXRAY => ["Women" => 1],
        self::VAGANZA => ["men" => 4],
        self::LUGGI => ["Baby" => 3],
        self::TOZLU => ["Women" => 1, "Man" => 2, "Kids" => 3],
        self::BARRELS => ["Women" => 1, "Man" => 2],
        self::PAZARIUM => ["Women" => 1],
        self::CASABONY => ["kids" => 1],
        self::VALIBERTA => ["men" => 1],
        self::VOLTAJ => ["men" => 1],
        self::BREEZE => ["kids" => 1],
        self::EMINNA => ["underwear and lıngerıe" => 1],
        self::MODAYAKAMOZ => ["all" => 1],
        self::MITELOVE => ["all" => 1],
        self::DENOKIDS => ["kids" => 1],
    ];

    const COMPANY_EMAILS = [
        self::TRENDYOL => 'ak4528462@gmail.com',
        self::ZEPKIDS => 'abdullahaydin@acarkids.com',
        self::ZEYLAND => 'serkan@zeynep.com.tr',
        self::TOUCHE => 'touche@example.com',
        self::BIGDART => 'info@bigdart.com.tr',
        self::MIXRAY => 'mixray@example.com',
        self::VAGANZA => 'vaganza@example.com',
        self::LUGGI => 'luggi@example.com',
        self::VOLTAJ => 'voltaj@example.com',
        self::TOZLU => 'g.arkun06@gmail.com',
        self::BARRELS => 'hakan.soyleyen@barrelsandoil.com',
        self::PAZARIUM => 'hakan.soyleyen@barrelsandoil.com',
        self::CASABONY => 'hakan.soyleyen@barrelsandoil.com',
        self::VALIBERTA => 'hakan.soyleyen@barrelsandoil.com',
        self::BREEZE => 'breeze@me.com',
        self::EMINNA => 'eminna@me.com',
        self::MODAYAKAMOZ => 'test@me.com',
        self::MITELOVE => 'MITELOVE@me.com',
        self::DENOKIDS => 'denokids@me.com',
    ];

    const getCompaniesValues = [
        self::TRENDYOL,
        self::ZEPKIDS,
        self::ZEYLAND,
        self::TOUCHE,
        self::BIGDART,
        self::MIXRAY,
        self::VAGANZA,
        // self::LUGGI,
        self::VOLTAJ,
        self::TOZLU,
        self::BARRELS,
        self::PAZARIUM,
        self::CASABONY,
        self::VALIBERTA,
        self::BREEZE,
        self::EMINNA,
        self::MODAYAKAMOZ,
        self::MITELOVE,
        self::DENOKIDS,
    ];

    public static function getCategories($company)
    {
        return self::COMPANY_CATEGORIES[$company] ?? [];
    }

    public static function getEmail($company)
    {
        return self::COMPANY_EMAILS[$company] ?? null;
    }
}
