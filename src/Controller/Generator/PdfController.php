<?php

    namespace App\Controller\Generator;

    use Twig\Environment;

    use Dompdf\Dompdf;
    use Dompdf\Options;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    class PdfController extends AbstractController 
    {
        #[Route('/pdf', name: 'app_pdf')]
        public function generatePdf(Environment $twig) : Response
        {

            $html = $twig->render('pdf.html.twig', [
                'title' => 'Welcome to our PDF Test',
            ]);

            // instantiate and use the dompdf class
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);

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
