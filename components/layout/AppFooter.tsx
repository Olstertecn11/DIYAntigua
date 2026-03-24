import { Box, Container, SimpleGrid, Stack, Text } from "@chakra-ui/react";

export default function AppFooter() {
  return (
    <Box bg="gray.900" color="white" py={12} mt={20}>
      <Container maxW="7xl">
        <SimpleGrid columns={{ base: 1, md: 3 }} gap={8}>
          <Stack>
            <Text fontWeight="bold">Antigua Transfers</Text>
            <Text color="gray.300">
              Traslados seguros, puntuales y cómodos.
            </Text>
          </Stack>

          <Stack>
            <Text fontWeight="bold">Servicios</Text>
            <Text color="gray.300">Traslados aeropuerto</Text>
            <Text color="gray.300">Rutas turísticas</Text>
            <Text color="gray.300">Reservas privadas</Text>
          </Stack>

          <Stack>
            <Text fontWeight="bold">Contacto</Text>
            <Text color="gray.300">WhatsApp</Text>
            <Text color="gray.300">Correo</Text>
            <Text color="gray.300">Políticas y privacidad</Text>
          </Stack>
        </SimpleGrid>
      </Container>
    </Box>
  );
}
