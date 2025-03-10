<?php

    namespace App\Controller\Generator;

    use Dompdf\Dompdf;
    use Dompdf\Options;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    class PdfController extends AbstractController 
    {
        #[Route('/pdf', name: 'app_pdf')]
        public function generatePdf() : Response
        {
            // instantiate and use the dompdf class
            $dompdf = new Dompdf();
            $dompdf->loadHtml('hello world je suis une brouette de merde');

            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'landscape');

            // Render le HTML en PDF
            $dompdf->render();
            
            // Télécharger ou afficher le PDF dans le navigateur
            $pdfContent = $dompdf->output();
                return new Response(
                    $pdfContent,
                    200,
                    ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="testbrouette.pdf"']
                );
        }
}
