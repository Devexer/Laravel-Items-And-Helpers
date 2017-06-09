<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeTrait extends Command
{
    /**
     * Different variables used
     */
    public $cliParameters = [];
    public $location;
    public $path;
    public $fileName;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {name : Name of Trait to create} {location=Traits : Folder location to place Trait}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Trait from a basic template';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->getParameters();
        $folder = $this->createFolder();
        if ($folder) {
            $file = $this->createFile();
            if ($file) {
                $this->info('Created trait with name '.$this->fileName);
                return;
            }
        }
        $this->error('Failed to create trait');
    }

    private function getParameters()
    {
        $this->cliParameters = $this->argument();
        $this->location = 'app/' . $this->cliParameters['location'];
        $this->fileName = $this->cliParameters['name'] . '.php';
        $this->path = base_path($this->location) . '/' . $this->cliParameters['name'] . '.php';
    }

    private function getTemplate()
    {
        $start = "<?php\n\n";
        $body = "namespace App\\" . $this->cliParameters['location'] . ';' . "\n\n";
        $end = "trait " . $this->cliParameters['name'] . "\n" . "{\n\n}";
        return $start . $body . $end;
    }

    private function createFile()
    {
        $template = $this->getTemplate();
        $file = fopen($this->path, "w");
        fwrite($file, $template);
        fclose($file);
        return $this;
    }

    private function createFolder()
    {
        if (!file_exists(base_path($this->location))) {
            mkdir(base_path($this->location));
        }
        return $this;
    }
}
