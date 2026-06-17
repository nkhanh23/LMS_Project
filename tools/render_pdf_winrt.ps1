param(
    [string]$InputPdf = "khanh-turning.pdf",
    [string]$OutputDir = "tools\khanh_turning_winrt"
)

Add-Type -AssemblyName System.Runtime.WindowsRuntime

$null = [Windows.Storage.StorageFile, Windows.Storage, ContentType = WindowsRuntime]
$null = [Windows.Data.Pdf.PdfDocument, Windows.Data.Pdf, ContentType = WindowsRuntime]
$null = [Windows.Storage.Streams.InMemoryRandomAccessStream, Windows.Storage.Streams, ContentType = WindowsRuntime]

function Await($AsyncAction, $ResultType) {
    $asTask = [System.WindowsRuntimeSystemExtensions].GetMethods() |
        Where-Object {
            $_.Name -eq 'AsTask' -and
            $_.GetParameters().Count -eq 1 -and
            $_.GetParameters()[0].ParameterType.Name -eq 'IAsyncOperation`1'
        } |
        Select-Object -First 1
    $task = $asTask.MakeGenericMethod($ResultType).Invoke($null, @($AsyncAction))
    $task.Wait()
    return $task.Result
}

$fullInput = (Resolve-Path -LiteralPath $InputPdf).Path
$out = New-Item -ItemType Directory -Force -Path $OutputDir
$file = Await ([Windows.Storage.StorageFile]::GetFileFromPathAsync($fullInput)) ([Windows.Storage.StorageFile])
$pdf = Await ([Windows.Data.Pdf.PdfDocument]::LoadFromFileAsync($file)) ([Windows.Data.Pdf.PdfDocument])

for ($i = 0; $i -lt $pdf.PageCount; $i++) {
    $page = $pdf.GetPage($i)
    $stream = New-Object Windows.Storage.Streams.InMemoryRandomAccessStream
    $options = New-Object Windows.Data.Pdf.PdfPageRenderOptions
    $options.DestinationWidth = [uint32]1600
    Await ($page.RenderToStreamAsync($stream, $options)) ([Windows.Foundation.IAsyncAction])
    $stream.Seek(0)
    $reader = New-Object Windows.Storage.Streams.DataReader($stream.GetInputStreamAt(0))
    $bytesToRead = [uint32]$stream.Size
    Await ($reader.LoadAsync($bytesToRead)) ([uint32])
    $bytes = New-Object byte[] $bytesToRead
    $reader.ReadBytes($bytes)
    $path = Join-Path $out.FullName ("page-{0}.png" -f ($i + 1))
    [System.IO.File]::WriteAllBytes((Resolve-Path $out.FullName).Path + "\page-$($i + 1).png", $bytes)
    Write-Output $path
    $page.Dispose()
}
