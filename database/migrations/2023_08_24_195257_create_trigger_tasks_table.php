<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE OR REPLACE FUNCTION before_update_insert()
            RETURNS trigger AS
            $$
            BEGIN
                IF NEW.is_completed IS TRUE THEN
                    NEW.completed_at := now();
                ELSE
                    NEW.completed_at := NULL;
                END IF;
            
                RETURN NEW;
            END;
            $$
            LANGUAGE 'plpgsql';
            
            CREATE TRIGGER trigger_update_insert
            BEFORE INSERT OR UPDATE
            ON tasks
            FOR EACH ROW
            EXECUTE PROCEDURE before_update_insert();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_update_insert ON tasks');
        DB::unprepared('DROP FUNCTION IF EXISTS before_update_insert');
    }
};
