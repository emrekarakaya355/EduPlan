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
            // scope_id: hangi birim veya bölümle
            // (App\Models\Birim veya App\Models\Bolum) tutar
            $table->unsignedBigInteger('scope_id')->nullable()->after('model_type');
            $table->string('scope_type')->nullable()->after('scope_id');

             // Bu, aynı kullanıcıya aynı rolü farklı kapsamlar için atamak için oluşturuldu.
            $table->dropPrimary('model_has_roles_role_model_type_primary');
            $table->primary([$pivotRole, $columnNames['model_morph_key'], 'model_type', 'scope_id', 'scope_type'],
                'model_has_roles_role_model_type_scope_primary');

            $table->index(['scope_id', 'scope_type']);
        });

        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotPermission) {
            $table->unsignedBigInteger('scope_id')->nullable()->after('model_type');
            $table->string('scope_type')->nullable()->after('scope_id');

            $table->dropPrimary('model_has_permissions_permission_model_type_primary');
            $table->primary([$pivotPermission, $columnNames['model_morph_key'], 'model_type', 'scope_id', 'scope_type'],
                'model_has_permissions_permission_model_type_scope_primary');

            $table->index(['scope_id', 'scope_type']);
        });
     }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';

        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotRole) {
            $table->dropPrimary('model_has_roles_role_model_type_scope_primary');
            $table->primary([$pivotRole, $columnNames['model_morph_key'], 'model_type'],
                'model_has_roles_role_model_type_primary');

            $table->dropIndex(['model_has_roles_scope_id_scope_type_index']);
            $table->dropColumn(['scope_id', 'scope_type']);
        });

        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotPermission) {
            $table->dropPrimary('model_has_permissions_permission_model_type_scope_primary');
            $table->primary([$pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                'model_has_permissions_permission_model_type_primary');

            $table->dropIndex(['model_has_permissions_scope_id_scope_type_index']);
            $table->dropColumn(['scope_id', 'scope_type']);
        });
    }
};
