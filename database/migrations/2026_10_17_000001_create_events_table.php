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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Event details
            $table->string('title');
            $table->string('slug')->unique(); // public link: negahm.ir/B/{slug}
            $table->string('event_type'); // concert, seminar, exhibition, ...
            $table->dateTime('event_date');
            $table->text('venue');
            $table->longText('description');

            // Visual identity
            $table->string('logo_path')->nullable();
            $table->string('brand_color', 9)->default('#000000');
            $table->string('secondary_color', 9)->nullable();

            // Rendering
            $table->string('template_name')->default('default-concert');
            $table->string('output_type', 20)->default('brochure'); // brochure | rsvp | invitation

            // Workflow
            $table->enum('status', ['draft', 'pending_review', 'active', 'expired'])->default('pending_review');
            $table->string('contact_phone', 40);
            $table->text('admin_notes')->nullable();
            $table->timestamp('activated_at')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('event_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
