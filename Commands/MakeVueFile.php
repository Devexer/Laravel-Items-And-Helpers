<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeVueFile extends Command
{
    public $cliParameters = [];
    public $location;
    public $path;
    public $fileName;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:vuefile {name : Name of component to create} {location=components : Folder location to place component}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a single file vuejs component.';

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
        $foler = $this->createFolder();
        if ($foler) {
            $file = $this->createFile();
            if ($file) {
                $this->info('Created vue component with name ' . $this->fileName);
                return;
            }
        }
        $this->error('Failed to create component');
    }

    private function getParameters()
    {
        $this->cliParameters = $this->argument();
        $this->location = 'resources/assets/js/' . $this->cliParameters['location'];
        $this->fileName = $this->cliParameters['name'] . '.vue';
        $this->path = base_path($this->location) . '/' . $this->cliParameters['name'] . '.vue';
    }

    private function getTemplate()
    {
        return "<template>\n\n</template>\n\n<style>\n\n</style>\n\n<script>\n export default{\n data(){\nreturn{\n\n};\n},\n mounted(){\n\n},methods:{\n}\n}\n</script>";
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
