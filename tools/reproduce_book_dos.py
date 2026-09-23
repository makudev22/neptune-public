from pathlib import Path
import subprocess
import sys


root = Path(__file__).resolve().parents[1]
php = root / "bin" / "php" / "php.exe"

if not php.is_file():
    raise SystemExit(f"PHP binary not found: {php}")

code = r'''
require "vendor/autoload.php";
$book = new \pocketmine\item\WritableBook();
try {
    $book->addPage(1000000000);
    fwrite(STDERR, "FAIL: unsafe page number was accepted\n");
    exit(1);
} catch (\InvalidArgumentException $e) {
    echo "PASS: unsafe page number rejected\n";
}
'''

result = subprocess.run(
    [str(php), "-d", "memory_limit=64M", "-r", code],
    cwd=root,
    text=True,
    capture_output=True,
)

sys.stdout.write(result.stdout)
sys.stderr.write(result.stderr)
print(f"Exit code: {result.returncode}")
