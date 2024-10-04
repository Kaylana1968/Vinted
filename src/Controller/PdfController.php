<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PdfController extends AbstractController
{
    private $pdf;

    public function __construct(Pdf $pdf)
    {
        $this->pdf = $pdf;
    }

    public function generatePdf(): Response
    {
        // Render the HTML template to a string
        $html = $this->renderView('main/receipts.html.twig', [
            'title' => 'Sample PDF',
            'content' => 'This is the content of the PDF.',
        ]);

        // Generate PDF from the rendered HTML
        $pdfContent = $this->pdf->getOutputFromHtml($html);

        // Create a response with the PDF content
        return new Response(
            $pdfContent,
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="receipts.pdf"',
            ]
        );
    }
}
