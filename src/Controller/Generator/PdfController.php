<?php

    namespace App\Controller\Generator;


    use Symfony\Component\HttpFoundation\Response;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use setasign\Fpdi\Fpdi;
    use setasign\Fpdi\PdfReader;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use mikehaertl\pdftk\Pdf;

    use App\Entity\User;


    class PdfController extends AbstractController
    {
        #[Route ('/pdf', name: 'app_pdf')]
        public function generatePdf(EntityManagerInterface $em) : Response
        {
            //On donne le chemin du PDF
            $pdfPath = $this->getParameter('kernel.project_dir') . '/public/pdf/2033-sd_5015form.pdf';

            // Vérifie si le fichier existe
            if (!file_exists($pdfPath)) {
                return new JsonResponse(['error' => 'Le fichier PDF est introuvable !'], 404);
            }

            //si l'id du formulaire correspond alors remplis sa valeur sinon null (par défault)
            $fieldValues = [
                'd#C3#A9signation_entreprise' => 'Salut tous le monde',
            ];

            //Définit les variables du PDF
            $pdf = new Pdf($pdfPath);

            //fillForm méthode qui permet de récupérer les champs form du PDF les analysers récupérer leurs id type etc et en paramètre peut permettre la modification de ces derniers.
            $pdf->fillForm($fieldValues)
                ->needAppearances()
                ->saveAs($this->getParameter('kernel.project_dir') . '/public/pdf/2033-sd_5015form_filled.pdf');

            //On Récupère le pdf via son pdfPath et on défini son type et sa disposition puis le génère
            return new Response(file_get_contents($this->getParameter('kernel.project_dir') . '/public/pdf/2033-sd_5015form_filled.pdf'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="2033-sd_5015form.pdf"',
            ]);
        }


        //Route qui permet de vérifier la totalité des champs présent dans un PDF.
        #[Route('/pdf/fields', name: 'app_pdf_fields')]
        public function getPdfFields(): JsonResponse
        {
            //Définit la route du PDF
            $pdfPath = $this->getParameter('kernel.project_dir') . '/public/pdf/2033-sd_5015form.pdf';

            //Vérifie que le PDF est bien trouvé sinon retourne une erreur
            if (!file_exists($pdfPath)) {
                return new JsonResponse(['error' => 'Le fichier PDF est introuvable !'], 404);
            }
            
            //Définit les variables du PDF
            $pdf = new Pdf($pdfPath);
            $fields = $pdf->getDataFields(); //Méthode getDataFields pour récupérer automatique les champs d'un pdf.

            //Vérifie que les champs du pdf sont bien récupérer sinon retourne une erreur
            if(!$fields) {
                return new JsonResponse("La Data du formulaire n'a pas été récupérées", 404);
            }
            
            //Définit la variable qui vas stockés les champs du PDF
            $fieldData = [];

            //Boucle pour parcourir tous les champs et les stockés dans la variable dédiée
            foreach ($fields as $field) {
                $fieldData[] = [
                    'FieldName' => $field['FieldName'] ?? null,
                    'FieldType' => $field['FieldType'] ?? null,
                    'FieldValue' => $field['FieldValue'] ?? null,
                ];
            }
    
            return new JsonResponse($fieldData);
        }
    }
