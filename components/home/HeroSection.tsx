"use client";

import Image from "next/image";
import {
  Box,
  Button,
  Container,
  Flex,
  Heading,
  HStack,
  Text,
  VStack,
} from "@chakra-ui/react";
import QuickBookingCard from "./QuickBookingCard"; // Asegúrate de importar tu formulario

export default function HeroSection() {
  return (
    // 1. Contenedor Principal: Define la altura y el área relativa para el posicionamiento
    <Box position="relative" w="full" minH={{ base: "auto", lg: "700px" }} pb={{ base: 20, lg: 0 }}>

      {/* 2. Imagen de Fondo Completa */}
      <Box position="absolute" inset={0} zIndex={-1}>
        <Image
          //src={`https://images.unsplash.com/photo-1526772662000-3f88f10405ff?auto=format&fit=crop&w=2000&q=80`}
          src={'/images/hero.webp'}
          // rotar horizontalmente y subir la imagen 

          alt="Traslados privados en Guatemala"
          fill
          priority
          style={{
            objectFit: "cover", objectPosition: "center", transform: "scaleX(-1)",
          }} // Rota la imagen horizontalmente
        />
        {/* Superposición oscura opcional para mejorar la legibilidad del texto blanco */}
        <Box position="absolute" inset={0} bg="blackAlpha.400" />
      </Box>

      <Container maxW="7xl" pt={{ base: 28, md: 36, lg: 44 }} position="relative">
        <Flex
          direction={{ base: "column", lg: "row" }}
          align={{ base: "center", lg: "flex-start" }}
          justify="space-between"
          gap={{ base: 12, lg: 10 }}
        >
          {/* 3. Columna de Texto (Izquierda) */}
          <VStack
            align={{ base: "center", lg: "flex-start" }}
            gap={6}
            maxW={{ base: "full", lg: "xl" }}
            color="white"
            textAlign={{ base: "center", lg: "left" }}
            pt={{ base: 0, lg: 10 }} // Pequeño ajuste para alinear con el form
          >
            {/* Tag de categoría */}
            <Text
              bg="rgba(255,255,255,0.2)"
              px={4}
              py={1}
              rounded="full"
              fontSize="sm"
              fontWeight="bold"
              textTransform="uppercase"
              letterSpacing="wider"
            >
              Premium + Funcional
            </Text>

            <Heading
              as="h1"
              fontSize={{ base: "4xl", md: "5xl", lg: "6xl" }}
              fontWeight="extrabold"
              lineHeight="1.1"
              letterSpacing="-2px"
            >
              Traslados privados y seguros en Guatemala.
            </Heading>

            <Text fontSize={{ base: "lg", md: "xl" }} color="gray.100" maxW="lg">
              Reserva en minutos tu traslado entre{" "}
              <strong style={{ color: "white" }}>Guate, Antigua, Panajachel y Quetzaltenango</strong>.
              Precio claro antes de confirmar.
            </Text>

            {/* Botones de Acción */}
            <HStack gap={4} pt={4}>
              <Button size="lg" colorScheme="blue" rounded="full" px={10}>
                Reservar ahora →
              </Button>
              <Button
                size="lg"
                variant="outline"
                color="white"
                borderColor="whiteAlpha.600"
                _hover={{ bg: "whiteAlpha.200" }}
                rounded="full"
              >
                Consultar reserva
              </Button>
            </HStack>

            {/* Checkmarks de confianza */}
            <HStack gap={6} pt={6} color="gray.200" fontSize="sm">
              <Text>✓ Conductores verificados</Text>
              <Text>✓ Soporte y confirmación</Text>
              <Text>✓ Reserva con o sin cuenta</Text>
            </HStack>
          </VStack>

          {/* 4. Columna del Formulario (Derecha) - EL TRUCO VISUAL */}
          <Box
            w={{ base: "full", md: "md", lg: "450px" }}
            // En escritorio (lg), lo movemos hacia abajo para que "flote" sobre el borde
            position={{ base: "relative", lg: "absolute" }}
            top={{ lg: "180px" }}
            right={{ lg: "0" }}
            zIndex={10}
          // Asegúrate de que el contenedor padre tenga position relative
          >
            <QuickBookingCard /> {/* Tu componente de formulario */}
          </Box>
        </Flex>
      </Container>
    </Box>
  );
}
