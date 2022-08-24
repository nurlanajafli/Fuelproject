<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hazmat}}`.
 */
class m210212_080730_create_hazmat_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hazmat}}', [
            'code' => $this->string(20),
            'description' => $this->text(),
        ]);
        $this->addPrimaryKey('hazmat_pk', '{{%hazmat}}', 'code');

        $this->batchInsert('{{%hazmat}}', ['code', 'description'], [
            ["Class A Explosives",      "Detonating or otherwise of maximum hazard."],
            ["Class B Explosives",      "In general, function by rapid combustion rather than detonation and include some explosive devices such as special fireworks, flash powders, etc. Flammable hazard."],
            ["Class C Explosives",      "Certain types of manufactured articles containing Class A or Class B explosives, or both, as components but in restricted quantities, and certain types of fireworks. Minimum hazard."],
            ["Combustible Liquid",      "Any liquid having a flash point at or above 100F and below 200 F under the conditions specified in Title 49, CFR."],
            ["Compressed Gas",          "Any material or mixture having in the container a pressure exceeding 40 psia at 70 F or 104 psia at 130 F."],
            ["Corrosive Substances",    "A material, liquid or solid that causes destruction or irreversible alteration to human skin tissue or a liquid that has a severe corrosion rate on steel or aluminum."],
            ["Etiologic Agent",         "An etiologic agent means a viable micro-organism, or its toxin, which causes or may cause human disease (Sec. 173.386 Refer to the Department of Health, Education and Welfare Regulations, Title 42, CFR)."],
            ["Explosives",              "Any chemical compound, mixture, or device the primary or common purpose of which is to function by explosion."],
            ["Flammable Gas",           "Any compressed gas meeting the requirements for lower flammability limit, flammability limit range, flame projection, or flame propagation criteria."],
            ["Flammable Liquid",        "Any liquid having a flash point below 100 F under the condition specified in Title 49"],
            ["Flammable Solids",        "Any solid material, other than explosive which is liable to cause fires through friction, absorption of moisture, spontaneous chemical changes, retained heat from manufacturing or processing, or which can be ignited readily and when ignited burns so vigorously and persistently as to create a serious transportation hazard."],
            ["Irritating Materials",    "Liquid or solid substances, which, upon contact with fire or when exposed to air, give off dangerous or intensely irritating fumes, but not including any poisonous material, Class A."],
            ["Nonflammable Gas",        "Any compressed gas other than a flammable compressed gas."],
            ["Organic Peroxide",        "An organic compound containing the vivalent -0-0- structure and which may be considered a derivative of hydrogen peroxide where one or more of the hydrogen atoms have been replaced by organic radicals must be classed as an organic peroxide."],
            ["ORM-A, B or C",           "Any material that does not meet the definition of a hazardous material, other than combustible liquid in packaging having a capacity of 110 gallons or less, and is specified in Sec. 172.101 as an ORM material or that possesses one or more of the characteristics described in ORM-A through D below (Sec. 173.500) Note: an ORM with a flash point of 100 F to 200 F, when transported with more than 110 gallons in one container shall be classed as a combustible liquid."],
            ["ORM-A",                   "Material which has an anesthetic, irritating, noxious, toxic or other similar property and which can cause extreme annoyance or discomfort to passengers and crew in the event of leakage during transportation."],
            ["ORM-B",                   "A material (including a solid when wet with water) capable of causing significant damage to a transport vehicle or vessel from leakage during transportation. Materials meeting one or both of the following criteria are ORM-B materials: (1) A liquid substance that has corrosion rate exceeding 0.250 inch per year (IPY) on aluminum (nonclad 7075-T6) at a test temperature of 130 F. An acceptable test is described in NACE Standard TM-01-69, and (2) specifically designated by name in Sec. 172.101 of the subchapter."],
            ["ORM-C",                   "A material which has other inherent characteristics not described as an ORM-A or ORM-B but which makes it unsuitable for shipment, unless properly identified and prepared for transportation."],
            ["ORM-D",                   "A material such as a consumer commodity which, through otherwise subject to the regulations of the subchapter, presents a limited hazard during transportation due to its form, quantity and packaging."],
            ["Oxidizer",                "A substance such as chlorate, permanganate, inorganic peroxide, notro carbo nitrate, or a nitrate, that yields oxygen readily to stimulate the combustion of organic matter."],
            ["Poison A",                "Extremely dangerous poisonous gases or liquids of such nature that a very small amount, mixed with air, is dangerous to life."],
            ["Poison B",                "Less dangerous poisons. Substances, liquids or solids (including pastes and semi-solid) other than Class A or irritating materials which are known to be so toxic to man as to afford a hazard to health during transportation, or which, in the absence of adequate data on human toxicity, are presumed to be toxic to man based on results with test animals."],
            ["Pyrophoric Liquid",       "Any liquid which may ignite spontaneously when exposed to air the temperature of which is 55 C (130 F) or below."],
            ["Radioactive Material",    "Any material or combination or materials, that spontaneously emits ionizing radiation and has a specific gravity greater than 0.002 microcuries per gram."],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%hazmat}}');
    }
}
