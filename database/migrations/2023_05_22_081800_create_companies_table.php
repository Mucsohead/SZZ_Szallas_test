<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //create companies table
        Schema::create('companies', function (Blueprint $table) {
            $table->id('companyId');
            $table->string('companyName');
            $table->string('companyRegistrationNumber')->unique();
            $table->date('companyFoundationDate');
            $table->string('country');
            $table->string('zipCode');
            $table->string('city');
            $table->string('streetAddress');
            $table->float('latitude');
            $table->float('longitude');
            $table->string('companyOwner');
            $table->integer('employes');
            $table->string('activity');
            $table->boolean('active');
            $table->string('email');
            $table->string('password');
        });

        //Try to set "companyFoundationDate" to readonly
        try{
            $this->makeFoundationReadOnly();
        }catch (Exception $e){
            Log::error('Failed to make the readonly trigger: '.$e->getMessage());
        }



    }

    /**
     * Prevent modification of the value of the column "companyFoundationDate"
     * @return void
     */
    private function makeFoundationReadOnly(): void
    {
        $triggerFunction = "CREATE OR REPLACE FUNCTION prevent_foundation_update()
                            RETURNS TRIGGER AS $$
                            BEGIN
                                IF NEW.\"companyFoundationDate\" <> OLD.\"companyFoundationDate\" THEN
                                    RAISE EXCEPTION 'The companyFoundationDate column cannot be modified.';
                                END IF;
                                RETURN NEW;
                            END;
                            $$ LANGUAGE plpgsql;";

        DB::statement($triggerFunction);

        $trigger = "CREATE OR REPLACE TRIGGER prevent_foundation_update_trigger
                        BEFORE UPDATE ON companies
                        FOR EACH ROW
                    EXECUTE FUNCTION prevent_foundation_update();";

        DB::statement($trigger);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
