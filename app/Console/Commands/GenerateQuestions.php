<?php
namespace App\Console\Commands;

use App\Services\QuestionGeneratorService;
use Illuminate\Console\Command;

class GenerateQuestions extends Command
{
    protected $signature = 'questions:generate {--per-template=20}';
    protected $description = 'Generate question instances from all active templates';

    public function handle()
    {
        $this->info('Generating questions...');
        $service = new QuestionGeneratorService();
        $result = $service->generateAll((int) $this->option('per-template'));
        $this->info('Generated ' . $result['total'] . ' questions.');
        foreach ($result['details'] as $d) {
            $this->line('  Template ' . $d['template_id'] . ': ' . $d['generated'] . ' new questions');
        }
    }
}
