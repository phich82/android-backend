<?php
namespace App\Migrations;

use App\DB;

class Migration
{
    public static function up(): void {
        $db = new DB();
        $db->query(file_get_contents(__DIR__.'/20241107_01__create_student_table.sql'));
        echo 'Table created successfully!';
    }

    public static function seed(): void {
        $db = new DB();
        $db->query(file_get_contents(__DIR__.'/20241107_02__seed_student_table.sql'));
        echo 'Seeded successfully!';
    }
}