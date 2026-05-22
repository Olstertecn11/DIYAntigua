<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('reservaciones')) {
            Schema::table('reservaciones', function (Blueprint $table) {
                if (! $this->columnExists('reservaciones', 'pago_provider')) {
                    $table->string('pago_provider', 50)->nullable()->after('estado_pago')->index();
                }

                if (! $this->columnExists('reservaciones', 'pago_referencia')) {
                    $table->string('pago_referencia')->nullable()->after('pago_provider')->index();
                }

                if (! $this->columnExists('reservaciones', 'pagado_at')) {
                    $table->timestamp('pagado_at')->nullable()->after('pago_referencia');
                }

                if (! $this->columnExists('reservaciones', 'pago_error_mensaje')) {
                    $table->string('pago_error_mensaje', 500)->nullable()->after('pagado_at');
                }
            });
        }

        if (! Schema::hasTable('payment_attempts')) {
            Schema::create('payment_attempts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('reservacion_id')->constrained('reservaciones')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('provider', 50)->default('qpaypro')->index();
                $table->string('flow', 50)->default('direct_card');
                $table->enum('status', ['pending', 'processing', 'approved', 'declined', 'error', 'cancelled', 'under_review'])->default('pending')->index();
                $table->decimal('amount', 10, 2);
                $table->char('currency', 3)->default('GTQ');
                $table->string('fingerprint_session_id')->nullable()->index();
                $table->string('idempotency_key')->unique();
                $table->string('client_ip_hash')->nullable();
                $table->string('user_agent_hash')->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('finished_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('payment_transactions')) {
            Schema::create('payment_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('payment_attempt_id')->constrained('payment_attempts')->cascadeOnDelete();
                $table->foreignId('reservacion_id')->constrained('reservaciones')->cascadeOnDelete();
                $table->string('provider', 50)->default('qpaypro')->index();
                $table->string('provider_transaction_id')->nullable()->index();
                $table->string('authorization_code')->nullable();
                $table->string('reference')->nullable();
                $table->enum('status', ['pending', 'approved', 'declined', 'error', 'cancelled', 'refunded', 'under_review'])->default('pending')->index();
                $table->string('response_code', 50)->nullable();
                $table->string('response_message', 500)->nullable();
                $table->decimal('amount', 10, 2);
                $table->char('currency', 3)->default('GTQ');
                $table->string('card_brand', 50)->nullable();
                $table->char('card_last_four', 4)->nullable();
                $table->longText('raw_response_sanitized')->nullable();
                $table->timestamp('verified_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('payment_callbacks')) {
            Schema::create('payment_callbacks', function (Blueprint $table) {
                $table->id();
                $table->string('provider', 50)->default('qpaypro')->index();
                $table->foreignId('reservacion_id')->nullable()->constrained('reservaciones')->nullOnDelete();
                $table->foreignId('payment_attempt_id')->nullable()->constrained('payment_attempts')->nullOnDelete();
                $table->foreignId('payment_transaction_id')->nullable()->constrained('payment_transactions')->nullOnDelete();
                $table->string('event_type', 100)->nullable();
                $table->string('status', 100)->nullable();
                $table->longText('payload_sanitized')->nullable();
                $table->boolean('signature_valid')->default(false);
                $table->boolean('processed')->default(false)->index();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('payment_security_events')) {
            Schema::create('payment_security_events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('reservacion_id')->nullable()->constrained('reservaciones')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('event_type', 100)->index();
                $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('low')->index();
                $table->string('description', 500)->nullable();
                $table->string('ip_hash')->nullable();
                $table->string('user_agent_hash')->nullable();
                $table->longText('metadata')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_security_events');
        Schema::dropIfExists('payment_callbacks');
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('payment_attempts');

        if (Schema::hasTable('reservaciones')) {
            Schema::table('reservaciones', function (Blueprint $table) {
                foreach (['pago_error_mensaje', 'pagado_at', 'pago_referencia', 'pago_provider'] as $column) {
                    if ($this->columnExists('reservaciones', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }

    private function columnExists(string $table, string $column): bool
    {
        return DB::table('information_schema.columns')
            ->whereRaw('table_schema = schema()')
            ->where('table_name', $table)
            ->where('column_name', $column)
            ->exists();
    }
};
