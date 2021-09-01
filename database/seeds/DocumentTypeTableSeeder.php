<?php

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('t_document_types')->delete();

		DocumentType::create([
			'dt_type' 		=> 'Letter',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'Announcement',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'Notice of Meeting',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'Memorandum',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'MOA',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'Administrative Order',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'Special Order',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'Minutes of Meeting',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'Publication',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'Resolution',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'Schedule',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'Fax Message',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'E-mail Message',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'Receipt',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'Executive Order',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'Primer',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'Form',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'Evaluation Form',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'Speech',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'Solicitation',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);

		DocumentType::create([
			'dt_type' 		=> 'Report',
			'created_at' 	=> '2016-08-16 08:00:00'
		]);
    }
}