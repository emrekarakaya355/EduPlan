<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';

        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotRole) {
            $table->unsignedBigInteger('scope_id')->nullable()->after('model_type');
            $table->string('scope_type')->nullable()->after('scope_id');

            $table->string('scope_key')->default('global')->after('scope_type');

            $table->dropPrimary();
            $table->primary(['role_id', 'model_id', 'model_type','scope_key'], 'model_has_roles_role_model_type_primary');
            $table->unique([$pivotRole, 'model_id', 'model_type', 'scope_key'], 'dp_model_has_roles_unique_scoped_role');

            $table->index(['scope_id', 'scope_type']);
        });

        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotPermission) {
            $table->unsignedBigInteger('scope_id')->nullable()->after('model_type');
            $table->string('scope_type')->nullable()->after('scope_id');

            $table->string('scope_key')->default('global')->after('scope_type');
            $table->dropPrimary();
            $table->primary(['permission_id', 'model_id', 'model_type','scope_key'], 'model_has_permissions_permission_model_type_primary');
            $table->unique([$pivotPermission, 'model_id', 'model_type', 'scope_key'], 'dp_model_has_permissions_unique_scoped_permission');


            $table->index(['scope_id', 'scope_type']);
        });
     }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');

        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) {
            $table->dropUnique('dp_model_has_roles_unique_scoped_role');
            $table->dropIndex(['scope_id', 'scope_type']);
            $table->dropColumn(['scope_id', 'scope_type', 'scope_key']);
        });

        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) {
            $table->dropUnique('dp_model_has_permissions_unique_scoped_permission');
            $table->dropIndex(['scope_id', 'scope_type']);
            $table->dropColumn(['scope_id', 'scope_type', 'scope_key']);
        });
    }
};
