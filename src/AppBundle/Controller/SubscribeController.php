<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Subscriber;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class SubscribeController extends Controller
{
    /**
     * @Route("/subscribe", name="show_subscribe_form")
     *
     * @param $request
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function showForm(Request $request)
    {
        $subscriber = new Subscriber();

        $form = $this->createFormBuilder($subscriber)
            ->add('email', EmailType::class)
            ->add('submit', SubmitType::class, ['label'=>'Subscribe'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $subscriber = $form->getData();

            //email check
            $user = $em->getRepository('AppBundle:Subscriber')->findOneBy(['email' => $subscriber->getEmail()]);
            if (!empty($user)) {
                return $this->render('subscribe/subscribe_info.html.twig', [
                    'status' => 'Such e-mail already subscribed!',
                    'page' => 'Subscribe',
                ]);
            }

            $subscriber->setKeyName(md5(uniqid($subscriber->getEmail(), true)));

            $em->persist($subscriber);
            $em->flush();

            return $this->render('subscribe/subscribe_info.html.twig', [
                'status' => 'Thank you! Now you will get notifications of the new articles daily.',
                'page' => 'Subscribe',
            ]);
        }

        return $this->render('subscribe/subscribe_form.html.twig', [
            'form' => $form->createView(),
            'page' => 'Subscribe',
        ]);
    }
}
