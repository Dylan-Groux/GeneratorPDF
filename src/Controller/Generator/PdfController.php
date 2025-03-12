<?php

    namespace App\Controller\Generator;


    use Symfony\Component\HttpFoundation\Response;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use setasign\Fpdi\Fpdi;
    use setasign\Fpdi\PdfReader;

    use App\Entity\User;


    class PdfController extends AbstractController
    {
        #[Route ('/pdf', name: 'app_pdf')]
        public function generatePdf(EntityManagerInterface $em) : Response
        {
            //Récupérer un utilisateur depuis la base de données
            $user = $em->getRepository(User::class)->find(1); // 1 = id utilisateur
            if (!$user) {
                throw $this->createNotFoundException("L'utilisateur n'a pas été trouvé");
            }

            //Récupérer Le nom de l'utilisateur
            $userName = $user->getName();
            $userFirstName = $user->getPrenom();


            //Logique de création du PDF
            $pdfPath = $this->getParameter('kernel.project_dir').'/public/pdf/2033-sd_5015.pdf';

            if(!file_exists($pdfPath))
            {
                throw $this->createNotFoundException('Le fichier PDF est introuvable');
            }
            

            // Charger le PDF existant avec FPDI
            $pdf = new Fpdi();
            $pdf->setSourceFile($pdfPath);
            $pageCount = $pdf->setSourceFile($pdfPath);
            //Boucle qui retourne la totalité des pages dispnobiles dans le pdf
            for ($pagePdf = 1; $pagePdf <= $pageCount; $pagePdf++) {
                $templateId = $pdf->importPage($pagePdf);
                $pdf->AddPage();
                $pdf->useTemplate($templateId);
            

            if ($pagePdf == 2) {
                // Définir la police et la taille
                $pdf->SetFont('Helvetica', 'B', 14);
                // Définir la position du texte (exemple : 50mm à droite, 100mm en hauteur)
                $pdf->SetXY(50, 100);
                // Ajouter du texte
                $pdf->Cell(0, 10, $userName, 0, 1);
                $pdf->SetXY(70, 100);
                $pdf->Cell(0, 10, $userFirstName, 0, 1);
            }
        }



        
            //Retourner le PDF généré en réponse
            $response = new Response($pdf->Output('document.pdf', 'I'));
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'inline; filename="document.pdf"');

            return $response;
        }
}
